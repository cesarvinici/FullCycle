import Product from "../../product-adm/domain/product-adm.entity";
import Address from "../value-object/address";
import Invoice from "./invoice.entity";

describe("Invoice Test", () => {

    it("Should calculate the correct total Price", () => {

        const address = new Address(
            "Rua 1",
            123,
            "Complemento 1",
            "São Paulo",
            "SP",
            "12345678"
        )

        const items = [
                new Product({
                    name: "Product 1",
                    description: "Product 1",
                    purchasePrice: 10,
                    stock: 10
                }),
                new Product({
                    name: "Product 2",
                    description: "Product 2",
                    purchasePrice: 20,
                    stock: 10
                })
            
        ]

            const invoice = new Invoice({
                name: "Customer 1",
                document: "12345678910",
                address: address,
                items: items
            })

            expect(invoice.total).toBe(36)
    })



});