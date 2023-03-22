import CustomerRepositoryInterface from "../../../domain/customer/repository/customer-repository.interface";
import { Customer, InputListCustomerDto, OutputListCustomerDto } from "./list.customer.dto";

export default class ListCustomerUseCase {
    private customerRepository: CustomerRepositoryInterface;

    constructor(customerRepository: CustomerRepositoryInterface) {
        this.customerRepository = customerRepository;
    }

    async execute(dto: InputListCustomerDto): Promise<OutputListCustomerDto> {
        const customers = await this.customerRepository.findAll();

        const customersDto: Customer[] = customers.map(customer => {
            return {
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
        });

        return {
            customers: customersDto
        }
    }
}