import typ_e_writer from "./typewriter.js";
import cl_ock from "./clock.js";
import ro_ta_tor from "./rotator.js";


document.querySelectorAll(".typewriter").forEach((el) => {
    typ_e_writer.write(el);
});

document.querySelectorAll(".clock").forEach((el) => {
    cl_ock.start(el);
});

document.querySelectorAll(".ws-background").forEach((el) => {
    ro_ta_tor.start(el);
});