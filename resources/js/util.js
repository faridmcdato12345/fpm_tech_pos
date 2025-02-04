export function getNestedValue(obj, path) {
    return path.split('.').reduce((acc, part) => acc && acc[part], obj);
}

export function getSellingPrice(data) {
    const sortedData = data
        .filter((item) => item.quantity > 0) // Only consider additions
        .sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

    let totalQuantity = data.reduce((sum, item) => sum + item.quantity, 0);
    let remainingQuantity = totalQuantity;

    for (let record of sortedData) {
        if (remainingQuantity <= record.quantity) {
            return record.selling_price;
        }
        remainingQuantity -= record.quantity;
    }

    return null; // In case no stock matches
}

export default function fOne() {
    document.addEventListener('keydown', (event) => {
        if(event.key === 'F1'){
            event.preventDefault()
            console.log("f1 pressed")
        }
    })
}

export function convertToCurrent(amount) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
    }).format(amount);

}
