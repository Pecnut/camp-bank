# Camp bank software

A point-of-sale and banking system designed for charity events, camps, and group activities. Built with PHP and JavaScript, this tuck shop-style system runs locally on a laptop without requiring an internet connection.

The system enables participants to use custom RFID cards for purchases. Each participant receives an RFID card that functions as their 'bank card' within the system. Administrators can load credit onto cards, and participants can make purchases using their available balance. The software automatically tracks individual balances and purchase history, with the ability to withdraw remaining balances as cash at the end of the event.

Note: **This system does not support real bank cards**. Only RFID-enabled cards issued for the event can be used for payments.

## Equipment used

Previously tested equipment includes:
* [Yarongtech 13.56MHz RFID cards](https://www.amazon.co.uk/dp/B01F52VQZ0) or [Hernas 13.56MHz RFID cards](https://www.amazon.co.uk/HERNAS-Rewritable-Mi-Fare-13-56MHz-Control/dp/B09P6DP96X) – approximately £30 for 100 cards
* [USB 13.56MHz card reader](https://www.amazon.co.uk/Reader-13-56Mhz-Smart-Contactless-Windows/dp/B09BFZ85J3) – approximately £20
* [Printable labels for card customization](https://www.amazon.co.uk/dp/B0C272TYMP) – approximately £3.50 for 360 labels

Each RFID card contains a unique identifier that the system uses to recognise individual users. Simply connect the card reader to the laptop running the software via USB.

## How users pay

* The user taps their RFID card on the reader
* If the RFID card is not already in the system, the user is prompted to enter their name. The system creates a new user with a balance of £0.
* If the RFID card is already in the system, the user is identified by their card and their balance is displayed.
* The user selects items from the shop, which are added to their basket
* The user taps their card again to pay for the items in their basket
* The system updates the user's balance and the shop's stock levels