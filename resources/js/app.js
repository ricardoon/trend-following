import Alpine from 'alpinejs';

window.Alpine = Alpine;

import Cleave from 'cleave.js'
import 'cleave.js/dist/addons/cleave-phone.br'

import tippy from 'tippy.js'
import 'tippy.js/dist/tippy.css'

import ApexCharts from 'apexcharts'

window.tippy = tippy
window.chart = ApexCharts

document.addEventListener('alpine:initialized', () => {
    window.tippy.setDefaultProps({ duration: 100, delay: 0 })
    window.tippy('[data-tippy-content]')
})

Alpine.start();
