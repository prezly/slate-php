<?php

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Mark;
use Prezly\Slate\Model\Value;

return new Value(
    new Document(
        [
            new Block('image', [], [
                'src' => 'http://memory.loc.gov/pnp/cph/3d00000/3d02000/3d02000/3d02067r.jpg',
            ]),
            new Block('header', [
                new Text('Return to Sender'),
            ]),
            new Block('paragraph', [
                new Inline('quote'),
                new Text('I gave a letter to the postman'),
                new Text("He put it his sack", [new Mark('bold')]),
                new Text('Bright in early next morning', [new Mark('underlined'), new Mark('bold')]),
                new Text('He brought my letter back', [new Mark('underlined')]),
            ]),
        ]
    )
);
