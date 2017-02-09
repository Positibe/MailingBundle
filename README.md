# PositibeMailingBundle

Bundle to create, send and track mails with Symfony 2

This bundle allow you send massive mails in Symfony 2 and Symfony 3. Track and give you statistics about all mail you
send. And the most important advantage is easily to use

## Requirements

* Symfony 2.3+
* See also the `require` section of [composer.json](composer.json)

## Installing

After you install the bundle you need to configure some parameters:

You can define the user name and the user address in order to send your emails with them, also you have to define the
urls of your aplicación to redirect it to the correct one site. You could have a different configuration by environment:

    [yaml]
    # app/config/parameters.yml
    parameters:
        # ...
        user_sender: "John Doe"
        user_address: "xxxxx@xxx.xx"
        base_url: http://localhost/incidencias/web/
