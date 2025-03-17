Mustaches:

- variables

      {{ variable }}

- HTML

      {{{ html }}}

- Bucles

      {{# elementos }}
         <li>elemento</li>
      {{/ elementos }}


- Condiciones

      {{# hasgrade }}
         Si hasgrade es positivo.
      {{/ hasgrade }}

      {{^ hasgrade }}
         Si hasgrade es negativo
      {{/ hasgrade }}


- Bloques

      {{> auth_cncsregister/bloques/bloque }}
      {{> name-plugin/ruta-completa-desde-templates }}
      {{> name-plugin/ruta-completa-desde-templates }}

- Cadenas de texto

      {{#str}} identificador, name-plugin {{/str}}

- Iconos

      {{#pix}} icon, auth_cncsregister, Esto es un icono {{/pix}}
      {{#pix}} carpeta/icon, ame-plugin, Esto es un icono {{/pix}}

- Bloques JavaScript

      {{#js}}
          console.log('Hola Mundo!');
      {{/js}}