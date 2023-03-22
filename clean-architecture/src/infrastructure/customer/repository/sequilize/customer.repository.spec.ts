import { Sequelize } from "sequelize-typescript";
import Address from "../../../../domain/customer/value-object/address";
import Customer from "../../../../domain/customer/entity/customer";
import Product from "../../../../domain/product/entity/product";
import CustomerModel from "./customer.model";
import ProductModel from "../../../product/repository/sequilize/product.model";
import CustomerRepository from "./customer.repository";

describe("Customer repository test", () => {

    let sequilize: Sequelize;

    beforeEach(async () => {
        sequilize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true },
        });

        sequilize.addModels([CustomerModel]);
        await sequilize.sync();
    });
    afterEach(async () => {
        await sequilize.close();
    });


    it("should create a customer", async () => {

        const customerRepository = new CustomerRepository();
        const customer = new Customer("1", "Customer 1");
        const address = new Address("Street 1", 1, "City 1", "State 1", "Zip 1");

        customer.changeAddress(address);
        customer.activate();
        customer.addRewardPoints(10);
        
        await customerRepository.create(customer);
        const customerModel = await CustomerModel.findOne({ where: { id: "1" }});

        expect(customerModel.toJSON()).toStrictEqual({
            id: customer.id,
            name: customer.name,
            street: customer.address.street,
            number: customer.address.number,
            city: customer.address.city,
            state: customer.address.state,
            zip: customer.address.zip,
            active: customer.isActive(),
            rewardPoints: customer.rewardPoints,
        });
    });

    it("should update a customer", async () => {
            
       const customerRepository = new CustomerRepository();
       const customer = new Customer("1", "Customer 1");
       const address = new Address("Street 1", 1, "City 1", "State 1", "Zip 1");

       customer.changeAddress(address);
       customer.activate();
       customer.addRewardPoints(10);
       
       await customerRepository.create(customer);

       customer.changeName("Customer 2");

       await customerRepository.update(customer);

       const customerModel = await CustomerModel.findOne({ where: { id: "1" }});

       expect(customerModel.toJSON()).toStrictEqual({
           id: customer.id,
           name: customer.name,
           street: customer.address.street,
           number: customer.address.number,
           city: customer.address.city,
           state: customer.address.state,
           zip: customer.address.zip,
           active: customer.isActive(),
           rewardPoints: customer.rewardPoints,
       });
    
    });

    it("should find a customer", async () => {
       
        const customerRepository = new CustomerRepository();
        const customer = new Customer("1", "Customer 1");
        const address = new Address("Street 1", 1, "City 1", "State 1", "Zip 1");

        customer.changeAddress(address);
        customer.activate();
        customer.addRewardPoints(10);
        
        await customerRepository.create(customer);

        const foundCustomer = await customerRepository.find("1");

        expect(foundCustomer).toEqual(customer);
    });


    it("Should throw an error when customer not found", async () => {
    
       const customerRepository = new CustomerRepository();

       expect(async () => {
              await customerRepository.find("XPTO");
         }).rejects.toThrowError("Customer not found");
    });


    it("should find all customers", async () => {
                        
        const customerRepository = new CustomerRepository();
        const customer1 = new Customer("1", "Customer 1");
        const address1 = new Address("Street 1", 1, "City 1", "State 1", "Zip 1");

        customer1.changeAddress(address1);
        customer1.activate();
        customer1.addRewardPoints(10);
        
        await customerRepository.create(customer1);

        const customer2 = new Customer("2", "Customer 2");
        const address2 = new Address("Street 2", 2, "City 2", "State 2", "Zip 2");

        customer2.changeAddress(address2);
        customer2.activate();
        customer2.addRewardPoints(20);
        
        await customerRepository.create(customer2);

        const foundCustomers = await customerRepository.findAll();

        expect(foundCustomers).toEqual([customer1, customer2]);
        expect(foundCustomers.length).toBe(2);
        expect(foundCustomers[0]).toEqual(customer1);
        expect(foundCustomers[1]).toEqual(customer2);        
    });

});