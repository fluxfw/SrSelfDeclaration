# SrSelfDeclaration ILIAS Plugin

Declaration of object members

This project is licensed under the GPL-3.0-only license

## Requirements

* ILIAS 6.0 - 7.999
* PHP >=7.2

## Installation

Start at your ILIAS root directory

```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
git clone https://github.com/fluxfw/SrSelfDeclaration.git SrSelfDeclaration
```

Update, activate and config the plugin in the ILIAS Plugin Administration

## Description

Current only courses are supported

### Config

You can configure it global for all objects (new) and adjust it per object if needed

#### Global

![Global config form](./doc/images/global_config_form.png)

#### Object

##### Tab

![Object config tab](./doc/images/object_config_tab.png)

##### Form

![Object config form](./doc/images/object_config_form.png)

### Declaration block

Object members, with read permission only, can set declaration once

#### Before fill

![Declaration block before fill](./doc/images/declaration_block_before_fill.png)

#### Form

![Declaration form](./doc/images/declaration_form.png)

#### After fill

![Declaration block after fill](./doc/images/declaration_block_after_fill.png)

### Declarations

The declarations of object can be viewed from object administrators

#### Tab

![Declarations tab](./doc/images/declarations_tab.png)

#### Table

![Declarations table](./doc/images/declarations_table.png)
