const cl_ock = (function () {
    const start = (el) => {
        const hour = el.querySelector(".hour");
        const minutes = el.querySelector(".minutes");
        const seconds = el.querySelector(".seconds");
        const check_seconds = setInterval(() => {
            const cd = new Date();
            hour.innerText = cd.getHours();
            minutes.innerText = `${cd.getMinutes()}`.padStart(2, '0');
            seconds.innerText = `${cd.getSeconds()}`.padStart(2, '0');
        }, 1000);
    };
    return {
        start,
    };
})();
export default cl_ock;
