<?php

/**
 * This file is part of the Propel package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Propel\Tests\Runtime\Collection;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\Collection\OnDemandCollection;
use Propel\Runtime\Propel;
use Propel\Tests\Helpers\Bookstore\BookstoreDataPopulator;
use Propel\Tests\Helpers\Bookstore\BookstoreEmptyTestBase;

/**
 * Test class for OnDemandCollection.
 *
 * @author Francois Zaninotto
 *
 * @group database
 */
class OnDemandCollectionTest extends BookstoreEmptyTestBase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        BookstoreDataPopulator::populate($this->con);
        Propel::disableInstancePooling();
        $this->books = PropelQuery::from('\Propel\Tests\Bookstore\Book')->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)->find();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->books = null;
        parent::tearDown();
        Propel::enableInstancePooling();
    }

    /**
     * @return void
     */
    public function testSetFormatter()
    {
        $this->assertTrue($this->books instanceof OnDemandCollection);
        $this->assertEquals(4, count($this->books));
    }

    /**
     * @return void
     */
    public function testKeys()
    {
        $i = 0;
        foreach ($this->books as $key => $book) {
            $this->assertEquals($i, $key);
            $i++;
        }
    }

    /**
     * @expectedException \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function testoffsetExists()
    {
        $this->books->offsetExists(2);
    }

    /**
     * @expectedException \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function testoffsetGet()
    {
        $this->books->offsetGet(2);
    }

    /**
     * @expectedException \Propel\Runtime\Exception\BadMethodCallException
     *
     * @return void
     */
    public function testoffsetSet()
    {
        $this->books->offsetSet(2, 'foo');
    }

    /**
     * @expectedException \Propel\Runtime\Exception\BadMethodCallException
     *
     * @return void
     */
    public function testoffsetUnset()
    {
        $this->books->offsetUnset(2);
    }

    /**
     * @return void
     */
    public function testToArray()
    {
        $this->assertNotEquals([], $this->books->toArray());
        // since the code from toArray comes from ObjectCollection, we'll assume it's good
    }

    /**
     * @expectedException \Propel\Runtime\Exception\BadMethodCallException
     *
     * @return void
     */
    public function testFromArray()
    {
        $this->books->fromArray([]);
    }
}
