/*
 * Copyright (C) CHEVEREAU Lazare - All Rights Reserved
 *
 * @project    phpavance
 * @file       tailwind.config.js
 * @author     CHEVEREAU Lazare
 * @date       17/01/2022 13:11
 */

module.exports = {
  content: [
    './templates/**/*.html.twig',
    './assets/**/*.{vue,jsx}',
    './vendor/symfony/twig-bridge/Resources/views/Form/*.html.twig'
  ],
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
