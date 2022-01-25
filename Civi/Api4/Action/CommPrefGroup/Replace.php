<?php

namespace Civi\Api4\Action\CommPrefGroup;

class Replace extends \Civi\Api4\Generic\BasicReplaceAction {

  /**
   * @inheritDoc
   */
  public function _run($result) {
    // add fake replace as afform expects it else it will throw an error
  }

}
