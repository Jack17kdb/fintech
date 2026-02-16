<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) : 'FINEX' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', sans-serif;
            background: linear-gradient(135deg, #e8eef7 0%, #f5f7fb 100%);
            min-height: 100vh;
            display: flex;
        }
        
        .container {
            width: 100%;
            max-width: 100%;
            display: flex;
            justify-content: center;
        }
        
        .card {
            background: #fcfcfc;
            padding: 60px 35px 40px 35px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            position: relative;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card > * {
            width: 100%;
            max-width: 600px;
        }
        
        .logo-section {
            position: relative;
            margin-bottom: 35px;
            height: 120px;
            max-width: 600px;
        }
        
        .logo-bg {
            position: absolute;
            top: -25px;
            left: -25px;
            width: 140px;
            height: 140px;
            background: linear-gradient(135deg, #4F7FFF 0%, #5B8EFF 100%);
            border-radius: 50% 50% 50% 0%;
            box-shadow: 0 8px 24px rgba(79, 127, 255, 0.25);
        }
        
        .finex-logo {
            position: absolute;
            top: 10px;
            left: 0;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
            font-size: 15px;
            color: white;
            padding: 8px 14px;
            z-index: 2;
        }
        
        .finex-logo::before {
            content: '◉';
            font-size: 18px;
        }
        
        .currency-icons {
            position: absolute;
            width: 100%;
            height: 100%;
        }
        
        .currency-icon {
            position: absolute;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            font-size: 16px;
            font-weight: 600;
            color: #4F7FFF;
        }
        
        .icon-1 { 
            top: 10px; 
            right: 60px; 
            background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
            color: #2E7D32;
        }
        
        .icon-2 { 
            top: 45px; 
            right: 15px; 
            background: linear-gradient(135deg, #E1F5FE 0%, #B3E5FC 100%);
            color: #0277BD;
        }
        
        .icon-3 { 
            top: 85px; 
            right: 45px; 
            background: linear-gradient(135deg, #F3E5F5 0%, #E1BEE7 100%);
            color: #6A1B9A;
        }
        
        .card-title {
            font-size: 23px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 28px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s;
            background: #fafbfc;
            color: #1a202c;
        }
        
        .form-control::placeholder {
            color: #a0aec0;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #4F7FFF;
            background: white;
            box-shadow: 0 0 0 3px rgba(79, 127, 255, 0.08);
        }
        
        .btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4F7FFF 0%, #5B8EFF 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(79, 127, 255, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 127, 255, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0px);
        }
        
        .form-footer {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: #718096;
        }
        
        .form-footer a {
            color: #4F7FFF;
            text-decoration: none;
            font-weight: 600;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .alert-danger {
            background: #FFF5F5;
            color: #C53030;
            border: 1px solid #FEB2B2;
        }
        
        .alert-success {
            background: #F0FFF4;
            color: #2F855A;
            border: 1px solid #9AE6B4;
        }
        
        .nav-tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #E2E8F0;
            margin-bottom: 28px;
            width: 100%;
            max-width: 100%;
            padding: 0;
        }
        
        .nav-tab {
            flex: 1;
            padding: 14px 10px;
            text-align: center;
            font-weight: 600;
            font-size: 13px;
            color: #718096;
            cursor: pointer;
            border: none;
            background: none;
            position: relative;
            transition: color 0.2s;
            text-decoration: none;
            display: block;
        }
        
        .nav-tab:hover {
            color: #4A5568;
        }
        
        .nav-tab.active {
            color: #4F7FFF;
        }
        
        .nav-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #4F7FFF;
        }
        
        .balance-card {
            background: linear-gradient(135deg, #4F7FFF 0%, #5B8EFF 100%);
            border-radius: 16px;
            padding: 24px;
            color: white;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
        }
        
        .balance-card::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
            pointer-events: none;
        }
        
        .balance-label {
            font-size: 13px;
            opacity: 0.95;
            margin-bottom: 6px;
            font-weight: 500;
        }
        
        .balance-amount {
            font-size: 32px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }
        
        .money-icon {
            position: absolute;
            right: 24px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 50px;
            opacity: 0.2;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 16px;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-addon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: #E2E8F0;
            color: #4A5568;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .input-addon:hover {
            background: #CBD5E0;
        }
    </style>
</head>
<body>
