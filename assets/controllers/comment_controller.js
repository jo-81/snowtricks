import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    async signaled({params}) {
        // const response = await fetch(`${params.path}`);
        // var data = await response.json();

        console.log(params);

        // let labelForm = this.element.querySelector("label");
        // labelForm.textContent = data.data;

        // if (params.remove) {
        //     setTimeout(() => {
        //         labelForm.textContent = '';
        //     }, 1000);
        // }
    }
}
