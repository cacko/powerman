import Typewriter from "../vendor/typewriter-effect/core";

const typ_e_writer = (function () {
  const write = (el) => {
    const typewriter = new Typewriter(el, {delay: 0});
    const pauseFor = Number(el.dataset.pause || 0);
    const toggleClass = el.dataset.toggle || null;
    const tplSelector = el.dataset.tpl || null;
    const lines = Array.from(document.querySelector(tplSelector).content.children).map((el) => el.innerHTML);
    lines.forEach((line) => {
      typewriter.typeString(line).pauseFor(pauseFor);
    });
    typewriter.callFunction(() => {
      typewriter.stop();
      toggleClass &&
        document
          .querySelectorAll(toggleClass)
          .forEach((el) => el.classList.toggle("hidden") && el.classList.toggle("visible"));
    });
    typewriter.start();
  };
  return {
    write,
  };
})();
export default typ_e_writer;
