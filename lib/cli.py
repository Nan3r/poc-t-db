#!/usr/bin/env python
# -*- coding: utf-8 -*-
# author = i@cdxy.me
# project = https://github.com/Xyntax/POC-T

import os.path
from lib.parse.cmdline import cmdLineParser
from lib.core.option import initOptions
from lib.controller.loader import loadModule, loadPayloads
from lib.core.common import setPaths, showDebugData, banner, systemQuit, openBrowser
from lib.core.data import paths, conf, logger, cmdLineOptions
from lib.core.enums import EXIT_STATUS
from lib.core.settings import IS_WIN
from lib.core.exception import ToolkitUserQuitException
from lib.core.exception import ToolkitMissingPrivileges
from lib.core.exception import ToolkitSystemException
from lib.controller.engine import run
from thirdparty.colorama.initialise import init as winowsColorInit
from plugin.mysql import insert


def main():
    """
    Main function of POC-T when running from command line.
    """
    try:
        paths.ROOT_PATH = os.path.dirname(os.path.dirname(os.path.realpath(__file__)))
        try:
            os.path.isdir(paths.ROOT_PATH)
        except UnicodeEncodeError:
            errMsg = "your system does not properly handle non-ASCII paths. "
            errMsg += "Please move the project root directory to another location"
            logger.error(errMsg)
            raise SystemExit
        setPaths()

        cmdLineOptions.update(cmdLineParser().__dict__)
        initOptions(cmdLineOptions)

        if IS_WIN:
            winowsColorInit()
        banner()

        if conf.DEBUG:
            showDebugData()

        loadModule()
        loadPayloads()

        run()
        insert(conf.MODULE_NAME)
        if conf.OPEN_BROWSER:
            openBrowser()

        systemQuit(EXIT_STATUS.SYSETM_EXIT)

    except ToolkitMissingPrivileges, e:
        logger.error(e)
        systemQuit(EXIT_STATUS.ERROR_EXIT)
    except ToolkitSystemException, e:
        logger.error(e)
        systemQuit(EXIT_STATUS.ERROR_EXIT)

    except ToolkitUserQuitException:
        systemQuit(EXIT_STATUS.USER_QUIT)
    except KeyboardInterrupt:
        systemQuit(EXIT_STATUS.USER_QUIT)


if __name__ == "__main__":
    main()
