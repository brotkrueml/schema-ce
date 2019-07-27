<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaCe\Tests\Fixtures\Model\Type;

/**
 * This file is part of the "schema_ce" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use Brotkrueml\Schema\Core\Model\AbstractType;

final class FixtureThing extends AbstractType
{
    protected $name;
    protected $description;
    protected $url;
    protected $image;
    protected $flag;
    protected $date;
}
