<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\User\Communication\Controller;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Spryker\Zed\User\Communication\Controller\EditController as SprykerEditController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\User\Business\UserFacadeInterface getFacade()
 * @method \Pyz\Zed\User\Communication\UserCommunicationFactory getFactory()
 */
class EditController extends SprykerEditController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $idUser = $this->castId($request->get(static::PARAM_ID_USER));
        if (empty($idUser)) {
            $this->addErrorMessage(static::MESSAGE_ID_USER_EXTRACT_ERROR);

            return $this->redirectResponse(static::USER_LISTING_URL);
        }
        $restrictedUserStore = $this->getFacade()->getCurrentUser()->getFkStore();
        if ($restrictedUserStore) {
            $editedUserStore = $this->getFacade()->findUser(
                (new UserCriteriaTransfer())->setIdUser($idUser)
            )->getFkStore();
            if ($restrictedUserStore !== $editedUserStore) {
                $this->addErrorMessage(static::MESSAGE_USER_NOT_FOUND);

                return $this->redirectResponse(static::USER_LISTING_URL);
            }
        }

        return parent::updateAction($request);
    }
}
