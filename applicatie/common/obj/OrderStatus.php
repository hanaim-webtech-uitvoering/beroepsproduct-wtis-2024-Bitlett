<?php

enum OrderStatus {
	case Pending;
	case InProduction;
	case Underway;
	case Complete;

	public static function from_int(?int $status): ?OrderStatus {
		switch($status) {
			case 0: return OrderStatus::Pending;
			case 1: return OrderStatus::InProduction;
			case 2: return OrderStatus::Underway;
			case 3: return OrderStatus::Complete;
		}
		return NULL;
	}

	public function to_int(): int {
		return match($this) {
			OrderStatus::Pending => 0,
			OrderStatus::InProduction => 1,
			OrderStatus::Underway => 2,
			OrderStatus::Complete => 3,
		};
	}

	public static function from_string(?string $status): ?OrderStatus {
		switch($status) {
			case "In Afwachting": return OrderStatus::Pending;
			case "In De Oven": return OrderStatus::InProduction;
			case "Onderweg": return OrderStatus::Underway;
			case "Voltooid": return OrderStatus::Complete;
		}
		return NULL;
	}

	public function to_string(): string {
		return match($this) {
			OrderStatus::Pending => "In Afwachting",
			OrderStatus::InProduction => "In De Oven",
			OrderStatus::Underway => "Onderweg",
			OrderStatus::Complete => "Voltooid",
		};
	}
}

?>

