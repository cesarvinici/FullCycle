import { Sequelize } from "sequelize-typescript";
import InoviceModel from "./invoice.model";
import Product from "../../product/domain/product.entity";
import Invoice from "../domain/invoice.entity";
import Id from "../../@shared/domain/value-object/id.value-object";
import Address from "../value-object/address";
import InvoiceRepository from "./invoice.repository";

describe("Invoice Repository Tests", () => {
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

        const repository = new InvoiceRepository();

        const items = [
            new Product({
                name: "Product 1",
                price: 10,
            }),
            new Product({
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

        
        await repository.generate(invoice);

        const invoiceGenerated = await InoviceModel.findOne({
            where: { id: invoice.id.id }}
        );

        expect(invoiceGenerated.id).toEqual(invoice.id.id);
        expect(invoiceGenerated.name).toEqual(invoice.name);
        expect(invoiceGenerated.document).toEqual(invoice.document);
        expect(invoiceGenerated.street).toEqual(invoice.address.street);
        expect(invoiceGenerated.number).toEqual(invoice.address.number);
        expect(invoiceGenerated.complement).toEqual(invoice.address.complement);
        expect(invoiceGenerated.city).toEqual(invoice.address.city);
        expect(invoiceGenerated.state).toEqual(invoice.address.state);
        expect(invoiceGenerated.zipCode).toEqual(invoice.address.zip);
        expect(invoiceGenerated.total).toEqual(30);
        expect(invoiceGenerated.items).toEqual(JSON.stringify(invoice.items));
    });


    it("Should find an Invoice by Id", async () => {
            
            const repository = new InvoiceRepository();
    
            const items = [
                new Product({
                    name: "Product 1",
                    price: 10,
                }),
                new Product({
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
    
            
            await InoviceModel.create({
                id: invoice.id.id,
                name: invoice.name,
                document: invoice.document,
                street: invoice.address.street,
                number: invoice.address.number,
                complement: invoice.address.complement,
                city: invoice.address.city,
                state: invoice.address.state,
                zipCode: invoice.address.zip,
                items: JSON.stringify(invoice.items),
                total: invoice.total
            });
    
            const invoiceGenerated = await repository.find(invoice.id.id);
    
            expect(invoiceGenerated.id).toEqual(invoice.id);
            expect(invoiceGenerated.name).toEqual(invoice.name);
            expect(invoiceGenerated.document).toEqual(invoice.document);
            expect(invoiceGenerated.address.street).toEqual(invoice.address.street);
            expect(invoiceGenerated.address.number).toEqual(invoice.address.number);
            expect(invoiceGenerated.address.complement).toEqual(invoice.address.complement);
            expect(invoiceGenerated.address.city).toEqual(invoice.address.city);
            expect(invoiceGenerated.address.state).toEqual(invoice.address.state);
            expect(invoiceGenerated.address.zip).toEqual(invoice.address.zip);
            expect(invoiceGenerated.items[0].name).toEqual(invoice.items[0].name);
            expect(invoiceGenerated.items[0].price).toEqual(invoice.items[0].price);
            
            expect(invoiceGenerated.items[1].name).toEqual(invoice.items[1].name);
            expect(invoiceGenerated.items[1].price).toEqual(invoice.items[1].price);
            expect(invoiceGenerated.total).toEqual(30);
    });


});