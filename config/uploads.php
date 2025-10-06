<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Product model upload settings
    |--------------------------------------------------------------------------
    |
    | Define the list of file extensions that are considered acceptable for 3D
    | product uploads. Administrators can add or remove extensions without
    | touching the controller or view logic.
    |
    */
    'product_model_extensions' => [
        'glb', 'gltf', 'obj', 'fbx', 'stl', 'dae', 'ply', '3ds', 'blend',
        'usd', 'usdz', 'step', 'stp', 'iges', 'igs', 'zip'
    ],

    'previewable_model_extensions' => [
        'glb', 'gltf'
    ],
];
