import Id from "../../@shared/domain/value-object/id.value-object";
import Product from "../../product-adm/domain/product.entity";
import Invoice from "../domain/invoice.entity";
import InvoiceGateway from "../gateway/invoice.gateway";
import Address from "../value-object/address";
import InoviceModel from "./invoice.model";

export default class InvoiceRepository implements InvoiceGateway {

    async generate(invoice: Invoice): Promise<void> {
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
    }


    async find(id: string): Promise<Invoice> {
        
        const result = await InoviceModel.findOne({
            where: { id: id }}
        );
        
        if (!result) {
            throw new Error("Invoice not found");
        }

        const items = JSON.parse(result.items).map((item: any) => {
            return new Product({
                id: new Id(item._id._id),
                name: item._name,
                description: item._description,
                purchasePrice: item._purchasePrice,
                stock: item._stock
            })
        });

        return new Invoice({
            id: new Id(result.id),
            name: result.name,
            document: result.document,
            address: new Address(
                result.street,
                result.number,
                result.complement,
                result.city,
                result.state,
                result.zipCode,
            ),
            items: items,
        });
    }
}