import './bootstrap';

import '../css/app.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
// document.addEventListener('DOMContentLoaded', function() {
//     const tabs = document.querySelectorAll('a[href^="#"]');
    


//     const tabContents = document.querySelectorAll('.tab-content');

//     tabs.forEach(tab => {
//         tab.addEventListener('click', function(event) {
//             event.preventDefault();

//             tabs.forEach(t => t.classList.remove('bg-blue-500', 'bg-green-500', 'bg-red-500', 'bg-yellow-500', 'text-white', 'font-semibold'));
//             tabContents.forEach(tc => tc.classList.add('hidden'));

//             event.currentTarget.classList.add('text-white', 'font-semibold');
//             document.getElementById(tab.getAttribute('href').substring(1)).classList.remove('hidden');

//             // Apply background color based on the tab
//             switch (tab.getAttribute('href')) {
//                 case '#images':
//                     tab.classList.add('bg-blue-500');
//                     break;
//                 case '#videos':
//                     tab.classList.add('bg-green-500');
//                     break;
//                 case '#audios':
//                     tab.classList.add('bg-red-500');
//                     break;
//                 case '#documents':
//                     tab.classList.add('bg-yellow-500');
//                     break;
//             }
//         });
//     });

//     // Activate the first tab by default
//     tabs[0].click();
// });




