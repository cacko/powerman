import random from "lodash-es/random";
import shuffle from "lodash-es/shuffle";

class _rotator {
    constructor(el) {
        this.el = el;
        this.images = Array.from(Array(7)).map((_, idx) => `/images/home/ws-background${idx}.webp`);
        this.setBackground();
    }

    start() {
        if (!this.timer) {
            this.timer = setTimeout(() => this.setBackground(), random(30, 45) * 1000);
        }
        return true;
    }

    stop() {
        return this.timer && clearTimeout(this.timer);
    }

    setBackground() {
        const src = shuffle(this.images)[0];
        this.el.style.backgroundImage = `url('${src}')`;
        this.start();
    };
};

export const Rotator = new _rotator(document.querySelector(".ws-background"));
