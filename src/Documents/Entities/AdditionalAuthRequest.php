<?php
/**
  * Cloud Agents library
  *
  * PHP version 5.4
  *
  * @author    João Rodrigues <joao.rodrigues@securibox.eu>
  * @copyright 2017 Securibox
  * @license   https://opensource.org/licenses/MIT The MIT License
  * @version   GIT: https://github.com/Securibox/cloudagents-php
  * @link      http://packagist.org/packages/securibox/cloudagents
  */

namespace Securibox\CloudAgents\Documents\Entities;
/**
 * Object representing the input for the additional authentification
 */
class AdditionalAuthRequest {

    /** @var string account ID */
    public $accountId;

    /** @var string The data for authentication */
    public $SbxSecretCode;
}
?>
