<?php

use Prezly\Slate\Model\Block;
use Prezly\Slate\Model\Document;
use Prezly\Slate\Model\Inline;
use Prezly\Slate\Model\Leaf;
use Prezly\Slate\Model\Mark;
use Prezly\Slate\Model\Text;
use Prezly\Slate\Model\Value;

return new Value(
    new Document(
        [
            new Block('image', [], [
                'src' => 'http://memory.loc.gov/pnp/cph/3d00000/3d02000/3d02000/3d02067r.jpg',
            ]),
            new Block('header', [
                new Text([
                    new Leaf('Return to Sender'),
                ]),
            ]),
            new Block('paragraph', [
                new Inline('quote'),
                new Text([
                    new Leaf('I gave a letter to the postman'),
                    new Leaf("He put it his sack", [new Mark('bold')]),
                    new Leaf('Bright in early next morning', [new Mark('underlined'), new Mark('bold')]),
                    new Leaf('He brought my letter back', [new Mark('underlined')]),
                ]),
            ]),
        ]
    )
);
