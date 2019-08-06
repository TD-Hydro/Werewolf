$(document).ready(function () {
    RoomInfo(GetUrlParam("room"), GetUrlParam("user"));
    ButtonFunction();
});

function GetUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}

var role = "";
var currentRoleList = [];
var player = 0;

function RoomInfo(room, username) {
    $.get("room.php", { room: room, user: username }, function (data, status) {
        var r = $.parseJSON(data);
        role = r.selfRole;
        currentRoleList = r.roleList;
        let playerList = r.playerList;
        let selfNo = r.No;
        let creator = r.creator;

        if (role == -1) {
            alert("房间已满！");
            window.history.back();
        }
        if (role == -2) {
            alert("该房间不存在！");
            window.history.back();
        }
        else {
            $("#popRole").find(".single-msg").html(RoleSearch(currentRoleList[role - 1]));
        }

        player = r.playerNo;
        $('#title').append("房间号：" + room);
        $('#i-room').val(room);
        $('#i-user').val(username);
        //append user
        for (let i = 1; i <= player; i++) {
            if (selfNo == i) {
                $('#playerlist').append("<li>" + username + "</li>");
                $('#chooseBoard').append("<label><input type=\"radio\" name=\"board\" value=\"board-" + i + "\">" + i + "." + username + "</label>");
            }
            else if (playerList[i]) {
                $('#playerlist').append("<li>" + playerList[i] + "</li>");
                $('#chooseBoard').append("<label><input type=\"radio\" name=\"board\" value=\"board-" + i + "\">" + i + "." + playerList[i] + "</label>");
            }
            else {
                $('#playerlist').append("<li></li>");
                $('#chooseBoard').append("<label><input type=\"radio\" name=\"board\" value=\"board-" + i + "\">" + i + "</label>");
            }
        }
        $('#b-room').val(room);
        $('#playerlist').listview();
        $("div").trigger('create');
        $('#exitRm').val(username);

        //no one can see creator ctrl
        if (creator != username) {
            $('.owner').css('display', 'none');
        }
    });
}

function ButtonFunction() {
    $("#reisssue").click(function () {
        location.href = "reissue.php?room=" + GetUrlParam('room') + "&playerNo=" + player;
    });
    $("#refresh").click(function () {
        location.reload()
    });
    $("#nightinfo").click(function () {
        $("#chooseTitle").html("昨夜信息");
        $("#chooseBoardForm").addClass("hide-choose");
        $('#witch-save').addClass("hide-choose");
        $("#b-check").addClass("hide-choose");
        $('#b-submit').addClass("hide-choose");
        $.get("play.php", { acquire3: true, item: "death1", item2: "death2", item3: "death3", room: GetUrlParam('room') }, function (data, status) {
            var deadInfo = "";
            var d = data.split(" ");
            for (let i = 0; i < d.length; i++) {
                if (parseInt(d[i]) != 0) {
                    deadInfo += d[i] + "号 ";
                }
            }
            if (deadInfo != "") {
                alert(deadInfo);
                $("#info-msg").html("昨夜被袭击的是：" + deadInfo);
            }
            else
                $("#info-msg").html("平安夜");
            $('#popChooseBoard').popup('open');
        });

    });
    $("#useAbility").click(function () {
        GetCurrentOrder(currentRoleList[role - 1], function (data, status) {
            if (data <= 0) {
                $("#chooseBoardForm").addClass("hide-choose");
                $('#popChooseBoard').popup('open');
            }
            else {
                $("#chooseBoardForm").removeClass("hide-choose");
                PrepareChooseBoard(currentRoleList[role - 1]);
            }
        });

    });
    $("#startGame").click(function () {
        $.get("play.php", { room: GetUrlParam('room'), start: true }, function (data, status) {
            if (data == "success") {
                $("#startGame").prop("disabled", true);
                setTimeout(function () { new Audio('voice/start.mp3').play(); }, 1000);
                setTimeout(function () { new Audio('voice/wolf1.mp3').play(); }, 5000);
                setTimeout(function () { new Audio('voice/wolf2.mp3').play(); }, 8000);
            }
            $("#startGame").addClass("ui-state-disabled");
        });

        var functionalRole = [];
        var intervalId = [];
        for (let index = 0; index < roleOrder.length; index++) {
            if (currentRoleList.indexOf(roleOrder[index]) != -1) {
                functionalRole.push(roleOrder[index]);
                intervalId.push(0);
            }
        }

        for (let index = 0; index < functionalRole.length; index++) {
            intervalId[index] = setInterval(function () {
                $.get("play.php", {
                    role: functionalRole[index], verify: true, room: GetUrlParam('room')
                }, function (data, status) {
                    if (data > 0) {
                        new Audio('voice/' + functionalRole[index] + 'e.mp3').play();
                        if (index < functionalRole.length - 1)
                            setTimeout(function () { new Audio('voice/' + functionalRole[index + 1] + 's.mp3').play(); }, 5000);
                        else
                            setTimeout(function () { new Audio('voice/sunrise.mp3').play(); }, 5000);
                        clearInterval(intervalId[index]);
                    }
                });
            }, 5000);
        }
    });

}

function RoleSearch(dashMark) {
    return roleList[dashMark];
}

var roleList = {
    "g-seer": "预言家",
    "g-witch": "女巫",
    "g-hunter": "猎人",
    "g-idiot": "白痴",
    "g-knight": "骑士",
    "g-guard": "守卫",
    "g-dreamtaker": "摄梦人",
    "g-magician": "魔术师",
    "w-white": "白狼王",
    "w-hidden": "隐狼",
    "w-king": "狼王",
    "w-devil": "恶魔",
    "w-beauty": "狼美人",
    "w-knight": "恶灵骑士",
    "w": "狼人",
    "f": "平民"
};

var roleOrder = ['g-magician', 'w', 'w-devil', 'w-beauty', 'g-witch', 'g-guard', 'g-seer', 'g-dreamtaker'];

function PrepareChooseBoard(selfRole) {

    if (selfRole[0] == 'w') {
        $("#chooseTitle").html("狼人");
        $("#small-title").html("请选择你要袭击的对象：");
        $('#witch-save').addClass("hide-choose");
        $("#b-check").addClass("hide-choose");
        $("#b-info").val("w");
        $('#popChooseBoard').popup('open');
    }
    if (selfRole[0] == "w" && selfRole[1] == "-") {
    }
    else if (selfRole == "g-witch") {
        //女巫
        $.get("play.php", { acquire3: true, item: "death1", item2: "g-witch", item3: "death2", room: GetUrlParam('room') }, function (data, status) {
            d = data.split(" ");
            $("#chooseTitle").html("女巫");
            $('#witch-save').removeClass("hide-choose");
            $('#witch-label').html("你已使用过解药。");
            if (d[1] != 0) {
                $('#witch-save').addClass("hide-choose");
            }
            else if (d[0] != 0) {
                $('#witch-label').html("昨天晚上被袭击的是" + d[0] + "号。是否使用解药？");
            }
            else {
                $('#witch-label').html("错误");
                $('#witch-save').addClass("hide-choose");
            }
            if (d[2] == 0) {
                $('#small-title').html("是否使用毒药？");
            }
            else {
                $('#small-title').html("你已使用过毒药。");
                $("#chooseBoardForm").addClass("hide-choose");
            }
            $("#b-info").val("g-witch");
            $("#b-check").addClass("hide-choose");
            $('#popChooseBoard').popup('open');
        });
    }
    else if (selfRole == "g-seer") {
        $("#chooseTitle").html("预言家");
        $("#small-title").html("请选择你要查验的对象：");
        $('#witch-save').addClass("hide-choose");
        $('#b-submit').addClass("hide-choose");
        $('#b-check').removeClass("hide-choose");
        $("#b-info").val("g-seer");
        $('#popChooseBoard').popup('open');
        $('#b-check').click(function () {
            var idCheck = $(".ui-radio-on").html().split('.')[0];
            $('#b-check').prop("disabled", true);
            $.get("view.php", {
                role: "g-seer",
                board: idCheck,
                room: GetUrlParam('room')
            }, function (data, status) {
                alert(data);
                var goodGuy = "好人";
                if (currentRoleList[data - 1][0] == "w" && currentRoleList[data - 1] != "w-hidden") {
                    goodGuy = "狼人"
                }
                $("#info-msg").html("他是 " + goodGuy);
                $.get("operation.php", {
                    "b-info": "g-seer", "b-room": GetUrlParam('room'), board: idCheck
                }, function (data, status) {
                });
            });
        });
    }
}

//role play

function CheckPredecessorStatus(preRole, callback) {
    $.get("play.php", { role: preRole, verify: true, room: GetUrlParam('room') }, callback);
}

function GetCurrentOrder(selfRole, callback) {
    //werewolf as a whole
    if (selfRole[0] == "w") {
        if (selfRole[1] == "-") {
            CheckPredecessorStatus('w', function (data, status) {
                if (data < 0) {
                    m = roleOrder.indexOf(selfRole);
                    if (m == -1)
                        callback(0, 0);
                    for (let i = m - 1; i >= 0; i--) {
                        if (currentRoleList.indexOf(roleOrder[i]) != -1 || roleOrder[i] == "w") {
                            CheckPredecessorStatus(roleOrder[i], callback);
                            break;
                        }
                    }
                }
            });
        }
        else {
            CheckPredecessorStatus('start', callback);
        }
    }
    if (selfRole[0] == "w" && selfRole[1] == "-") {
        callback(0, 0);
    }
    else if (selfRole == 'g-magician') {
        CheckPredecessorStatus('start', callback);
    }
    else {
        m = roleOrder.indexOf(selfRole);
        if (m == -1)
            callback(0, 0);
        for (let i = m - 1; i >= 0; i--) {
            if (currentRoleList.indexOf(roleOrder[i]) != -1 || roleOrder[i] == "w") {
                CheckPredecessorStatus(roleOrder[i], callback);
                break;
            }
        }
    }
}


