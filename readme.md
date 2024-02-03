[![Doe com PayPal](https://img.shields.io/badge/paypal-Contribua%20com%20o%20desenvolvimento-blue?style=for-the-badge&logo=paypal&link=https://www.paypal.com/donate/?hosted_button_id=TZ984YJ3SJEQA)](https://www.paypal.com/donate/?hosted_button_id=TZ984YJ3SJEQA)

[![Doe com PayPal](https://img.shields.io/badge/pix-Ajude%20a%20manter%20plugins%20gratuitos%20com%20Pix-blue?style=for-the-badge&logo=pix&color=%2300b7a9&link=https://nubank.com.br/pagar/16gd05/2YML7GG3gW)](https://nubank.com.br/pagar/16gd05/2YML7GG3gW)

# Frete grátis para Art-i Melhor Envio/Marketplace

Ofereça frete grátis e permita que seus vendedores gerem etiquetas com [Art-i Melhor Envio/Marketplace](https://art-idesenvolvimento.com.br/wordpress/plugins/frete-melhor-envio-marketplace/).

## Como usar

### Admin

Antes de tudo, crie e teste uma regra ou cupom de frete grátis pelo WooCommerce ou pelo marketplace (consulte o manual do seu marketplace para saber como adicionar a regra por vendedor).

### Instalação

A instalação é feita com o pacote ZIP disponível [aqui](https://github.com/Art-iDev/arti-frete-gratis-me/releases/download/v0.9.0/arti-frete-gratis-me-0.9.0.zip). Após o download, basta enviar o arquivo normalmente no painel administrativo.

Ou usando a ferramenta WP-CLI: `wp plugin install https://github.com/Art-iDev/arti-frete-gratis-me/releases/download/v0.9.0/arti-frete-gratis-me-0.9.0.zip --activate`

### Vendedor

O plugin adicionará um campo ao painel do Melhor Envio do vendedor permitindo que ele escolha qual dos métodos do Melhor Envio - Correios, JadLog, LATAM etc - ele quer usar para enviar os pacotes vendidos com frete grátis, ou seja, qual etiqueta será criada no seu painel. No carrinho, a estimativa de entrega será a mesma do método escolhido.

![image](https://github.com/Art-iDev/arti-frete-gratis-me/assets/700448/674bc1b9-d509-4291-9139-409b5be9e9c9)

_Exemplo do campo numa instalação Dokan padrão_

Para esconder os demais métodos e exibir somente o frete grátis, use o seguinte filtro:
```
<?php

add_filter( 'arti_frete_gratis_me_esconder_outros_metodos', '__return_true' );
```

Pode ocorrer de a cotação selecionada pelo vendedor não ser retornada pela API do Melhor Envio devido a regras das transportadoras, então é possível usar o seguinte filtro para que seja usada a cotação mais barata retornada:
```
<?php

add_filter( 'arti_frete_gratis_me_usar_cotacao_mais_barata', '__return_true' );
```
