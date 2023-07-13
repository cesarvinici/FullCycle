import { Sequelize } from "sequelize-typescript";
import InoviceModel from "../repository/invoice.model";
import InvoiceFacadeFactory from "./invoice.facade.factory";
import Product from "../../product/domain/product.entity";
import Id from "../../@shared/domain/value-object/id.value-object";
import InvoiceRepository from "../repository/invoice.repository";
import Address from "../value-object/address";
import Invoice from "../domain/invoice.entity";

describe("InvoiceFacadeFactory", () => {
    let sequelize: Sequelize;

    beforeEach(async () => {
        sequelize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true },
        });
        sequelize.addModels([InoviceModel])
        await sequelize.sync();
    });

    afterEach(async () => {
        await sequelize.close();
    });


    it("Should Generate an Invoice", async () => {

       
        const facade = InvoiceFacadeFactory.create();


        const items = [
            new Product({
                id: new Id("1"),
                name: "Product 1",
                price: 10,
            }),
            new Product({
                id: new Id("2"),
                name: "Product 2",
                price: 20,
            })
        ]

        const input = {
            id: "1",
            name: "John Doe",
            document: "123456789",
            street: "Street 1",
            number: 10,
            complement: "Complement 1",
            city: "City 1",
            state: "State 1",
            zipCode: "12345678",
            items: items,
        }


        await facade.generateInvoice(input);
        
        const repository = new InvoiceRepository();
        const invoice = await repository.find("1");

        expect(invoice).not.toBeNull();
        expect(invoice.id.id).toBe("1");
        expect(invoice.name).toBe("John Doe");
        expect(invoice.document).toBe("123456789");
        expect(invoice.address.street).toBe("Street 1");
        expect(invoice.address.number).toBe(10);
        expect(invoice.address.complement).toBe("Complement 1");
        expect(invoice.address.city).toBe("City 1");
        expect(invoice.address.state).toBe("State 1");
        expect(invoice.address.zip).toBe("12345678");
        expect(invoice.items.length).toBe(2);
        expect(invoice.items[0].name).toBe("Product 1");
        expect(invoice.items[0].price).toBe(10);
        expect(invoice.items[1].name).toBe("Product 2");
        expect(invoice.items[1].price).toBe(20);
        expect(invoice.total).toBe(30);
    });

    it("Should Find an Invoice", async () => {
            
        const facade = InvoiceFacadeFactory.create();

        const items = [
            new Product({
                id: new Id("1"),
                name: "Product 1",
                price: 10,
            }),
            new Product({
                id: new Id("2"),
                name: "Product 2",
                price: 20,
            })
        ]

        const address = new Address("Street 1", 10, "Complement 1", "City 1", "State 1", "12345678")

        const invoice = new Invoice({
            id: new Id("1"),
            name: "John Doe",
            document: "123456789",
            address: address,
            items: items,
        });

        
        const repository = new InvoiceRepository();
        await repository.generate(invoice);


        const result = await facade.findInvoice({id: "1"});

        expect(result).not.toBeNull();
        expect(result.id).toBe("1");
        expect(result.name).toBe("John Doe");
        expect(result.document).toBe("123456789");
        expect(result.address).toEqual({
            street: "Street 1",
            number: 10,
            complement: "Complement 1",
            city: "City 1",
            state: "State 1",
            zipCode: "12345678",
        });

        expect(result.items.length).toBe(2);
        expect(result.items[0]).toEqual({
            id: "1",
            name: "Product 1",
            price: 10,
        });

        expect(result.items[1]).toEqual({
            id: "2",
            name: "Product 2",
            price: 20,
        });

    });
});