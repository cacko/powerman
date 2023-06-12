const ro_ta_tor = (function () {

    const IMAGES = Array.from(Array(7)).map((_, idx) => `/images/ws-background${idx}.webp`);
    const start = (el) => {
        const timer = setInterval(() => {
            const src = IMAGES.shift();
            console.log(src, IMAGES);
            el.style.backgroundImage = `url('${src}')`;
            IMAGES.push(src);
        }, Math.random() * 20000);
    };
    return {
        start,
    };
})();
export default ro_ta_tor;
