<?php

return (object) [
    'object'   => 'value',
    'document' => (object) [
        'object' => 'document',
        'data'   => (object) [],
        'nodes'  => [
            (object) [
                'object' => 'block',
                'type'   => 'image',
                'isVoid' => false,
                'data'   => (object) ['src' => 'http://memory.loc.gov/pnp/cph/3d00000/3d02000/3d02000/3d02067r.jpg'],
                'nodes'  => [],
            ],
            (object) [
                'object' => 'block',
                'type'   => 'header',
                'isVoid' => false,
                'data'   => (object) [],
                'nodes'  => [
                    (object) [
                        'object' => 'text',
                        'leaves' => [
                            (object) [
                                'object' => 'leaf',
                                'text'   => 'Return to Sender',
                                'marks'  => [],
                            ],
                        ],
                    ],
                ],
            ],
            (object) [
                'object' => 'block',
                'type'   => 'paragraph',
                'data'   => (object) [],
                'isVoid' => false,
                'nodes'  => [
                    (object) [
                        'object' => 'inline',
                        'type'   => 'quote',
                        'isVoid' => false,
                        'nodes'  => [],
                        'data'   => (object) [],
                    ],
                    (object) [
                        'object' => 'text',
                        'leaves' => [
                            (object) [
                                'object' => 'leaf',
                                'text'   => 'I gave a letter to the postman',
                                'marks'  => [],
                            ],
                            (object) [
                                'object' => 'leaf',
                                'text'   => 'He put it his sack',
                                'marks'  => [
                                    (object) ['object' => 'mark', 'type' => 'bold', 'data' => (object) []],
                                ],
                            ],
                            (object) [
                                'object' => 'leaf',
                                'text'   => 'Bright in early next morning',
                                'marks'  => [
                                    (object) [
                                        'object' => 'mark',
                                        'type'   => 'underlined',
                                        'data'   => (object) [],
                                    ],
                                    (object) ['object' => 'mark', 'type' => 'bold', 'data' => (object) []],
                                ],
                            ],
                            (object) [
                                'object' => 'leaf',
                                'text'   => 'He brought my letter back',
                                'marks'  => [
                                    (object) [
                                        'object' => 'mark',
                                        'type'   => 'underlined',
                                        'data'   => (object) [],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
