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
                'data'   => (object) ['src' => 'http://memory.loc.gov/pnp/cph/3d00000/3d02000/3d02000/3d02067r.jpg'],
                'nodes'  => [],
            ],
            (object) [
                'object' => 'block',
                'type'   => 'header',
                'data'   => (object) [],
                'nodes'  => [
                    (object) [
                        'object' => 'text',
                        'text'   => 'Return to Sender',
                        'marks'  => [],
                    ],
                ],
            ],
            (object) [
                'object' => 'block',
                'type'   => 'paragraph',
                'data'   => (object) [],
                'nodes'  => [
                    (object) [
                        'object' => 'inline',
                        'type'   => 'quote',
                        'nodes'  => [],
                        'data'   => (object) [],
                    ],
                    (object) [
                        'object' => 'text',
                        'text'   => 'I gave a letter to the postman',
                        'marks'  => [],
                    ],
                    (object) [
                        'object' => 'text',
                        'text'   => 'He put it his sack',
                        'marks'  => [
                            (object) ['object' => 'mark', 'type' => 'bold', 'data' => (object) []],
                        ],
                    ],
                    (object) [
                        'object' => 'text',
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
                        'object' => 'text',
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
];
