import Invoice from "../domain/invoice.entity";

export default interface InvoiceGateway {
    generate(invoice: Invoice): Promise<any>;
    find(id: string): Promise<any>;
}