import { toXML } from "jstoxml";
import { OutputListCustomerDto } from "../../../usecase/customer/list/list.customer.dto";

export default class CustomerPresenter {

    static toXml(data: OutputListCustomerDto): string {
       
        const xmlOptions = {
            header: true,
            indent: "  ",
            nenwLine: "\n",
            allowEmpty: true,
        };

        return toXML({
            customers: data.customers.map(customer => {
                return {
                    customer: {
                        id: customer.id,
                        name: customer.name,
                        address: {
                            street: customer.address.street,
                            number: customer.address.number,
                            city: customer.address.city,
                            state: customer.address.state,
                            zip: customer.address.zip,
                        }
                    }
                }
            })
        }, xmlOptions);
    }
}