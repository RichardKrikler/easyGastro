"use strict";
$(function () {

    function timeCecker() {
        setInterval(function () {
            let storedTimeStamp = sessionStorage.getItem("lastTimeStamp");
            timeCompare(storedTimeStamp);
        }, 3000);
    }

    function timeCompare(timeString) {
        let currentTime = new Date();
        let pastTime = new Date(timeString);
        let timeDiff = currentTime - pastTime;
        let minPast = Math.floor((timeDiff / 60000));
        console.log(minPast);

        if (minPast > 1) {
            sessionStorage.removeItem("lastTimeStamp");
            window.location.href = "destroySession.php";
            return false;
        } else {
            console.log(currentTime + " - " + pastTime + " - " + minPast);
        }

    }

    $(document).mousemove(function () {
        let timestamp = new Date();
        sessionStorage.setItem("lastTimeStamp", timestamp);
    })

    timeCecker();
});