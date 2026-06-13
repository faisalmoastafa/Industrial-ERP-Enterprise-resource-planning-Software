# NECI Manufacturing Usage Guide

This guide explains how to use the production batch module for super enamel copper wire manufacturing.

## 1. Create Product Records First

Create stock products for both raw material and finished goods.

Example raw material products:

- Old Copper Wire
- Generator Copper Coil
- Transformer Copper Coil
- Fresh Copper Wire

Example finished products:

- Super Enamel Copper Wire 15 SWG
- Super Enamel Copper Wire 16 SWG
- Super Enamel Copper Wire 17 SWG

Use `kg` as the unit.

## 2. Purchase Raw Material

Go to Purchases and buy the raw copper product.

Example:

```text
Product: Old Copper Wire
Quantity: 1000 kg
Cost: 900 per kg
```

After the purchase is completed, raw material stock increases.

## 3. Create Production Batch

Go to:

```text
Manufacturing → Create Batch
```

Fill the batch in three parts.

### Raw Material Input

Select the raw material product and enter how many kg are going into production.

Example:

```text
Old Copper Wire: 1000 kg
Cost per kg: 900
```

### Conversion Expenses

Enter production costs.

Example:

```text
Labor: 20,000
Electricity: 15,000
Enamel / Chemical: 25,000
Machine Cost: 10,000
```

### Finished Output

Select finished wire products and enter output kg.

Example:

```text
Super Enamel Copper Wire 15 SWG: 300 kg
Super Enamel Copper Wire 16 SWG: 250 kg
Super Enamel Copper Wire 17 SWG: 350 kg
```

If input is 1000 kg and output is 900 kg, the system records:

```text
Wastage: 100 kg
```

## 4. What The System Does

When the batch is completed:

- raw material stock decreases
- finished wire stock increases
- conversion expense is added to batch cost
- wastage kg is recorded
- cost per finished kg is calculated
- finished product cost is updated by weighted average

## 5. Sell Finished Product

After production, sell finished wire through Sales or Sales Terminal.

Profit becomes more accurate because the finished product has a real production cost.

## Important Rule

Do not create a production batch until the raw material product has enough stock.

Do not delete a batch after finished goods are already sold, because the system cannot safely reverse stock that no longer exists.
