# Defining a Template for Coding Standard Documentation

The robo command `document:codestyle` produces a Documentation for a coding standard.
In difference to the built-in function of PHP CodeSniffer, GreenCape/robo provides a template based output.
You can not only provide an HTML template, but also XML, JSON, or whatever you want.
All you have to do is to provide a corresponding Twig template.

## Data Source

PHP CodeSniffer supports auto-documentation. For that purpose, sniff collections for coding standards should
provide information in the `Docs` directory of the standard. This information is stored in XML files having the following format:

```xml
<documentation title="Title of the Sniff">
    <standard>
    <![CDATA[
    Description of the rule/s, that is/are checked by this sniff.
    ]]>
    </standard>
    <code_comparison>
        <code title="Valid: Description of why this is right.">
        <![CDATA[
Sample code showing how it is done right. The significant locations are highlighted using <em></em>.
        ]]>
        </code>
        <code title="Invalid: Description of why this is wrong.">
        <![CDATA[
Sample code showing a wrong way. The significant locations are highlighted using <em></em>.
        ]]>
        </code>
    </code_comparison>
</documentation>
```

`standard` and `code_comparision` blocks can occur in any order and number.
A `code_comparision` block contains exactly two code blocks, the first one always showing the *valid* code example. 

## Data Structure

The Twig template is fed with a data structure, that contains the information of all available XML documentation files
used by the coding standard, determined by the `ruleset.xml` file and the content of the standard's own `Sniffs`
directory.

The first level of the data structure contains the fields

  - `standard` - The name of the coding standard. If a path was supplied as parameter for the standard, the name is
    derived from that path. In the template, the value is accessed using `{{ standard }}`.
  - `version` - The version of PHP CodeSniffer. In the template, the value is accessed using `{{ version }}`.
  - `rules` - The list of available rules. In the template, the list is accessed in a loop.
  
PHP CodeSniffer groups the sniffs by what they are checking, f.x. 'Classes' or 'NamingConventions'.
Each standard may add its own groups.

The next level - the entries of the `rules` field - consists of those groups, ordered alphabetically by the group's name.
The groups are traversed using `{% for group in rules %}`.
A group has the fields

  - `name` - The name of the group. In the template, the value is accessed using `{{ group.name }}`.
  - `sniffs` - The list of sniffs in the group. In the template, the list is traversed using `{% for sniff in group.sniffs %}`
  
A sniff again consists of these fields:

  - `title` - The documentation title from the XML file. In the template, the value is accessed using `{{ sniff.title }}`.
  - `id` - A URL-safe string identifier, suitable for use as element `id` and `href` reference. In the template, the value is accessed using `{{ sniff.id }}`.
  - `name` - The name of the sniff in dot notation, i.e., 'Standard.Group.SniffName'. In the template, the value is accessed using `{{ sniff.name }}`.
  - `blocks` - The list of `standard` and `code_comparision` blocks from the XML file. In the template, the list is traversed using `{% for block in sniff.blocks %}`
  
A *text* block (from the `standard` section of the XML file) contains

  - `type` - Always 'text'. In the template, the value is accessed using `{{ block.type }}`.
  - `content` - The text from the XML file. It is already HTML-safe, with `<em></em>` being the only allowed tag. In the template, the value is accessed using `{{ block.content|raw }}`.
    The `raw` filter is needed to prohibit the text to be escaped twice.
    
In a *code* block, `content` has additional sub-fields:

  - `type` - Always 'code'. In the template, the value is accessed using `{{ block.type }}`.
  - `content.valid.title` The title for the valid code example. In the template, the value is accessed using `{{ block.content.valid.title }}`.
  - `content.valid.code` The valid code example. In the template, the value is accessed using `{{ block.content.valid.code|raw }}`.
    The `raw` filter is needed to prohibit the text to be escaped twice.
  - `content.invalid.title` The title for the invalid code example. In the template, the value is accessed using `{{ block.content.invalid.title }}`.
  - `content.invalid.code` The invalid code example. In the template, the value is accessed using `{{ block.content.invalid.code|raw }}`.
    The `raw` filter is needed to prohibit the text to be escaped twice.

## Sample Template

The following is a sample template, that shows the use of the Twig codes (`{{ ... }}` and `{% ... %}` sequences).
Feel free to change the code around and between them to fit your needs.

```twig
<!DOCTYPE html>
<html>
    <head>
        <title>{{ standard }} Coding Standards</title>
        <style>
            .code-comparison {
                width: 100%;
            }

            .code-comparison td, .code-comparison th {
                text-align: left;
                vertical-align: top;
                padding: 4px;
                width: 50%;
                border: 1px solid;
            }

            .code-comparison-code {
                font-family: Courier, monospace;
            }

            .code-comparison-code em {
                line-height: 15px;
                font-style: normal;
            }

            .valid em {
                background-color: #DFD;
                border: 1px solid #5A5;
            }

            .invalid em {
                background-color: #FDD;
                border: 1px solid #E55;
            }
        </style>
    </head>
    <body>
        <h1>{{ standard }} Coding Standards</h1>

        <div>
            {% for group in rules %}
            <a href="#{{ group.name }}"><h3>{{ group.name }}</h3></a>
            <ul class="toc">
                {% for sniff in group.sniffs %}
                <li><a href="#{{ sniff.id }}">{{ sniff.title }}</a></li>
                {% endfor %}
            </ul>
            {% endfor %}
        </div>

        <div>
            {% for group in rules %}
            <h2 id="{{ group.name }}">{{ group.name }}</h2>
            {% for sniff in group.sniffs %}
            <h3 id="{{ sniff.id }}">{{ sniff.title }} ({{ sniff.name }})</h3>

            {% for block in sniff.blocks %}
            {% if block.type == 'text' %}
            <p class="text">{{ block.content|raw }}</p>
            {% endif %}
            {% if block.type == 'code' %}
            <table class="code-comparison">
                <tr>
                    <th class="code-comparison-title valid">{{ block.content.valid.title }}</th>
                    <th class="code-comparison-title invalid">{{ block.content.invalid.title }}</th>
                </tr>
                <tr>
                    <td class="code-comparison-code valid"><code>{{ block.content.valid.code|raw }}</code></td>
                    <td class="code-comparison-code invalid"><code>{{ block.content.invalid.code|raw }}</code></td>
                </tr>
            </table>
            {% endif %}
            {% endfor %}
            {% endfor %}
            {% endfor %}
        </div>

        <div>
            Documentation generated on {{ "now"|date() }} by
            <a href="">GreenCape/robo</a>
            using
            <a href="https://github.com/squizlabs/PHP_CodeSniffer">PHP_CodeSniffer {{ version }}</a>
        </div>
    </body>
</html>
```
