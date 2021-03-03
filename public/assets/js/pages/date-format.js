const className = 'js-date-format';

const timeElapsed = {
    initialize: function () {
        timeElapsed.assign();
        setInterval(function () {
            timeElapsed.loop();
        }, 1000);
    },
    find: function () {
        return document.getElementsByClassName(className);
    },
    assign: function () {
        let elements = timeElapsed.find()
        for (let i = 0; i < elements.length; i++) {
            elements[i].setAttribute(
                'data_time',
                new Date(elements[i].innerHTML).toISOString().split('.')[0] + "Z");
        }
    },
    iterate: function (elements) {
        for (let i = 0; i < elements.length; i++) {
            elements[i].innerHTML = timeElapsed.rewrite(elements[i].getAttribute('data_time'));
        }
    },
    rewrite: function (time) {
        const SECONDS_IN_A_MINUTE = 60;
        const SECONDS_IN_AN_HOUR = 3600;
        const SECONDS_IN_A_DAY = 86400;

        let date = new Date(time);
        let now = new Date();
        now = new Date(now.toISOString().split('.')[0] + "Z");

        let diff = Math.abs(date - now) / 1000;

        if (diff < SECONDS_IN_A_MINUTE) {
            return diff + ' seconds  ago';
        } else if (diff < SECONDS_IN_AN_HOUR) {
            let minutes = Math.floor((diff / SECONDS_IN_A_MINUTE));
            let desc = ((minutes === 1) ? 'minute' : 'minutes');

            return `${minutes} ${desc} ago`;
        } else if (diff < SECONDS_IN_A_DAY) {
            let hours = Math.floor((diff / SECONDS_IN_AN_HOUR));
            let desc = ((hours === 1) ? 'hour' : 'hours');

            return `${hours} ${desc} ago`;
        }

        return time;
    },
    loop: function () {
        timeElapsed.iterate(timeElapsed.find());
    }
};
