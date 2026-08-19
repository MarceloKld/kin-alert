<?php

return [
    'cloud_url' => env('cloudinary://375714451437862:-ME7G_mzy5MxBGgDcHlm6rQClWA@jjmpouzm'),

    'upload_preset' => env('kin_alert_preset'),
    
    // Si le package demande spécifiquement "cloud", vérifie qu'il y a bien cette structure :
    'cloud' => [
        'cloud_name' => env('jjmpouzm'),
        'api_key'    => env('375714451437862'),
        'api_secret' => env('-ME7G_mzy5MxBGgDcHlm6rQClWA'),
    ],
];