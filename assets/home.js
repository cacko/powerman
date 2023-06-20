import "./styles/pages/home.sass";
import typ_e_writer from "./js/typewriter.js";
import cl_ock from "./js/clock.js";
import {Rotator} from "./js/rotator.js";
import DateTimePicker from "material-datetime-picker";

const picker = new DateTimePicker()
    .on('submit', (val) => console.log(`data: ${val}`))
    .on('open', () => console.log('opened'))
    .on('close', () => console.log('closed'));

document.querySelector('.c-datepicker-btn')?.addEventListener('click', () => picker.open());

document.querySelectorAll(".typewriter").forEach((el) => {
    typ_e_writer.write(el);
});

document.querySelectorAll(".clock").forEach((el) => {
    cl_ock.start(el);
});

window.addEventListener("load", () => {
    Rotator.start();
});
window.addEventListener("beforeunload", () => {
    Rotator.stop()
});
