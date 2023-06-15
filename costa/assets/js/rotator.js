class _rotator {
    constructor(el) {
        this.el = el;
        this.images = Array.from(Array(7)).map((_, idx) => `../images/home/ws-background${idx}.webp`);
        this.setBackground();
    }

    start() {
        this.timer = setInterval(() => this.setBackground(), Math.random() * 20000);
    }

    stop() {
        this.timer && clearInterval(this.timer);
    }

    setBackground() {
        const src = this.images.shift();
        this.el.style.backgroundImage = `url('${src}')`;
        this.images.push(src);
    };
};

export const Rotator = new _rotator(document.querySelector(".ws-background"));
