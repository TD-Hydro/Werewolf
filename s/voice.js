$(document).ready(function () {
    CheckOngoingProcess();
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


});

function CheckOngoingProcess() {
    let functionalRole = [];
    let intervalId = [];
    for (let index = 0; index < roleOrder.length; index++) {
        console.log(roleOrder[index],currentRoleList.indexOf(roleOrder[index]), currentRoleList);
        if (currentRoleList.indexOf(roleOrder[index]) != -1) {
            functionalRole.push(roleOrder[index]);
            intervalId.push(0);
        }
    }

    $.get("play.php", {
        ongoing: true, room: GetUrlParam('room')
    }, function (data, status) {
        let r = $.parseJSON(data);
        if (parseInt(r.start) != 0) {
            let startingIndex = -1;
            for (let index = 0; index < functionalRole.length; index++) {
                let role = functionalRole[index];
                
                if (r[role] == "null") {
                    continue;
                }
                else if (parseInt(r[role]) > 0) {
                    startingIndex = index;
                    break;
                }
            }
            for (let index = startingIndex; index < functionalRole.length; index++) {
                intervalId[index] = setInterval(function () {
                    console.log(index,functionalRole[index]);
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
        }
    });
}