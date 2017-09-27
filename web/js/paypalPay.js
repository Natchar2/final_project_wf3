 paypal.Button.render({
    env: 'sandbox',
    client: {
        sandbox:    'AbB8aZ2BYsMvDfrV9MFhOxkhnwgaUawUSka2QPVwNfOrU5CU7h6ubG9BRiSx3UQ8233Ip-XgxYGhvnkg',
        production: 'TENYC2U2Y6DL6'
    },
    commit: true,
    payment: function(data, actions) {
        return actions.payment.create({
            payment: {
                transactions: [
                    {
                        amount: { total: '1.00', currency: 'EUR' }
                    }
                ]
            }
        });
    },
    onAuthorize: function(data, actions) {
        return actions.payment.execute().then(function(payment) {
            window.alert('Payment Complete!');
        });
    }
}, '#paypal-button');