<?php

jaxon()->sentry()->addViewRenderer('raintpl', function () {
    return new Jaxon\RainTpl\View();
});
