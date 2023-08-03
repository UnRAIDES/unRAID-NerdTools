# unRAID-NerdTools
[![](https://badgen.net/badge/icon/github?icon=github&label)](https://github.com/UnRAIDES)
[![](https://badgen.net/badge/icon/docker?icon=docker&label)](https://hub.docker.com/r/unraides)
![Github stars](https://badgen.net/github/stars/UnRAIDES/unRAID-NerdTools?icon=github&label=stars)
![Github forks](https://badgen.net/github/forks/UnRAIDES/unRAID-NerdTools?icon=github&label=forks)
![Github last-commit](https://img.shields.io/github/last-commit/UnRAIDES/unRAID-NerdTools)
![Github license](https://badgen.net/github/license/UnRAIDES/unRAID-NerdTools)

![](images/logo.png)

**Install and Uninstall extra packages easily.**


![](images/image01.png)


##

[NerdTools](https://forums.unraid.net/topic/129200-plug-in-nerdtools/) is an Unraid 6.11+ plugin allowing installation of additional Slackware packages. This is based off the old NerdPack plugin ([GitHub](https://github.com/dmacias72/unRAID-NerdPack), [Forum](https://forums.unraid.net/topic/35866-unraid-6-nerdpack-cli-tools-iftop-iotop-screen-kbd-etc/)) for Unraid 6.10 and earlier.

This plugin uses its GitHub repo as a source of truth for which packages are available, what their dependencies are, and also stores the packages themselves. Upon picking a package for installation, NerdTools will download the package and its dependencies to the Unraid `/boot/extra` directory, a special location on the USB Flash disk which will auto-install any package within at reboot time. It then installs the package using `upgradepkg` for immediate use. 