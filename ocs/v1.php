<?php
/**
 * @author Bart Visscher <bartv@thisnet.nl>
 * @author Frank Karlitschek <frank@owncloud.org>
 * @author Lukas Reschke <lukas@owncloud.com>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 * @author Tom Needham <tom@owncloud.com>
 * @author Vincent Petry <pvince81@owncloud.com>
 *
 * @copyright Copyright (c) 2015, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */
require_once '../lib/base.php';

if (\OCP\Util::needUpgrade()) {
	// since the behavior of apps or remotes are unpredictable during
	// an upgrade, return a 503 directly
	OC_Response::setStatus(OC_Response::STATUS_SERVICE_UNAVAILABLE);
	$response = new OC_OCS_Result(null, OC_Response::STATUS_SERVICE_UNAVAILABLE, 'Service unavailable');
	OC_API::respond($response, OC_API::requestedFormat());
	exit;
}

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

try {
	// load all apps to get all api routes properly setup
	OC_App::loadApps();

	// api calls always will return English
	\OC_L10N::forceLanguage('en');

	OC::$server->getRouter()->match('/ocs'.\OC::$server->getRequest()->getRawPathInfo());
} catch (ResourceNotFoundException $e) {
	OC_API::setContentType();
	OC_OCS::notFound();
} catch (MethodNotAllowedException $e) {
	OC_API::setContentType();
	OC_Response::setStatus(405);
}

