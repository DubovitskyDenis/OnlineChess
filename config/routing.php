<?php

return [
    'GET game/<game_id:\d+>' => 'game/play',
    'POST game/cancel/<game_id>' => 'game/cancel',
    'POST game/surrender/<game_id>' => 'game/surrender',
    'GET game/<game_id>/move/<from_x>:<from_y>:<to_x>:<to_y>' => 'game/move'
];