<?php

jaxon()->di()->getViewManager()->addRenderer('raintpl', function () {
    return new Jaxon\RainTpl\View();
});
