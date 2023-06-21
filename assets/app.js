import './styles/app.sass';
import {Rotator} from "./js/rotator.js";
import {Animator} from "./js/animator";

const onAway = () => Rotator.stop() && Animator.stop();
const onActive = () => Rotator.start() && Animator.start()

window.addEventListener("load", onActive);
window.addEventListener("beforeunload", onAway);

window.addEventListener("focus", onActive);
window.addEventListener("blur", onAway);
