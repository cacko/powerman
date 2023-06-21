import random from "lodash-es/random";

class _rotator {
    constructor(el) {
        this.el = el;
        this.images = Array.from(Array(7)).map((_, idx) => `/images/home/ws-background${idx}.webp`);
        this.setBackground();
    }

    start() {
        this.timer = setInterval(() => this.setBackground(), random(30, 45) * 1000);
        return !!this.timer;
    }

    stop() {
        return this.timer && clearInterval(this.timer);
    }

    setBackground() {
        const src = this.images.shift();
        this.el.style.backgroundImage = `url('${src}')`;
        this.images.push(src);
    };
};

export const Rotator = new _rotator(document.querySelector(".ws-background"));
