import Address from "../../domain/entity/address";
import Customer from "../../domain/entity/customer";
import CustomerRepositoryInterface from "../../domain/repository/customer-repository.interface";
import CustomerModel from "../db/sequelize/model/customer.model";
import ProductModel from "../db/sequelize/model/product.model";

export default class CustomerRepository implements CustomerRepositoryInterface {
   

    async create(entity: Customer): Promise<void> {
        await CustomerModel.create({
            id: entity.id,
            name: entity.name,
            street: entity.address.street,
            number: entity.address.number,
            city: entity.address.city,
            state: entity.address.state,
            zip: entity.address.zip,
            active: entity.isActive(),
            rewardPoints: entity.rewardPoints,
        });
    }
    async update(entity: Customer): Promise<void> {
        await CustomerModel.update({
            name: entity.name,
            street: entity.address.street,
            number: entity.address.number,
            city: entity.address.city,
            state: entity.address.state,
            zip: entity.address.zip,
            active: entity.isActive(),
            rewardPoints: entity.rewardPoints,

        }, {
            where: { id: entity.id }
        });

    }

    async find(id: string): Promise<Customer> {
        let customerModel;
        
        try {
            customerModel = await CustomerModel.findOne(
                { 
                    where: { id },
                    rejectOnEmpty: true 
                }
            );
        } catch (error) {
            throw new Error("Customer not found");
        }

        const customer = new Customer(customerModel.id, customerModel.name);
        const address = new Address(customerModel.street, customerModel.number, customerModel.city, customerModel.state, customerModel.zip);
        customer.changeAddress(address);
        
        customerModel.active ? customer.activate() : customer.deactivate();
        customer.addRewardPoints(customerModel.rewardPoints);

        return customer;
    }

    async findAll(): Promise<Customer[]> {
        const customerModels = await CustomerModel.findAll();
        const customers: Customer[] = [];

        customerModels.forEach(customerModel => {
            const customer = new Customer(customerModel.id, customerModel.name);
            const address = new Address(
                customerModel.street,
                customerModel.number,
                customerModel.city,
                customerModel.state,
                customerModel.zip
            );
            
            customer.changeAddress(address);
            
            if (customerModel.active) {
                customer.activate();
            }

            customer.addRewardPoints(customerModel.rewardPoints);

            customers.push(customer);
        });

        return customers;
    }

}