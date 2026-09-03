################################################################################
#
#  This file is part of SplashSync Project.
#
#  Copyright (C) Splash Sync <www.splashsync.com>
#
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
#
#  For the full copyright and license information, please view the LICENSE
#  file that was distributed with this source code.
#
#  @author Bernard Paquier <contact@splashsync.com>
#
################################################################################

################################################################################
# Sellsy Api Sandbox Image for Testing
#
# Based on splashsync/openapi-sandbox:3.0, with the Entities, Controllers and
# configuration that fake the Sellsy Api V2.
#
# The Api itself stays open: MAIN_SECURITY_ROLE is left to its default, so
# tests can read it directly. The Oauth2 server is still fully working, and an
# Oauth2 application is registered at startup, so the connector Oauth2 flow can
# be exercised for real.
################################################################################

FROM splashsync/openapi-sandbox:3.0

################################################################################
# Oauth2 Application, Registered at Container Startup
#
# Not secrets: the well known credentials of a throwaway test sandbox.
ENV OAUTH2_CLIENT_ID=SellsySandbox
ENV OAUTH2_CLIENT_SECRET=SellsySandboxSecret
ENV OAUTH2_CLIENT_REDIRECT_URI=http://toolkit.sellsy.local/ws/sellsy

################################################################################
# Copy Sellsy Api Definitions
COPY Entity/ /app/src/Entity/
COPY Controller/ /app/src/Controller/
COPY Serializer/ /app/src/Serializer/
COPY EventListener/ /app/src/EventListener/
COPY Migrations/ /app/migrations/
COPY sellsy.yaml /app/config/packages/sellsy.yaml

################################################################################
# Regenerate autoloader to include new classes, then rebuild cache
RUN rm -rf /app/var/cache/*
RUN composer dump-autoload --no-dev --optimize
RUN php bin/console cache:clear --no-debug
