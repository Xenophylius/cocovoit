<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('firstname')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('lastname')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('password')
                ->password()
                ->nullable()
                ->minLength(8),
            Forms\Components\TextInput::make('role')
                ->maxLength(255),
                Forms\Components\MultiSelect::make('trip_id')
                ->relationship('trip', 'id') // Assurez-vous d'adapter ceci à votre relation réelle et au champ d'affichage
                ->searchable() // Permet à l'utilisateur de rechercher des options
                ->preload() // Précharge les options pour améliorer les performances
                ->placeholder('Sélectionnez les trajets'), // Texte d’espace réservé
                FileUpload::make('avatar')
                    ->nullable()
                    ->disk('public')
                    ->directory('avatars')
                    ->previewable() 
                    ->image()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('firstname'),
            Tables\Columns\TextColumn::make('lastname'),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('role'),
            Tables\Columns\TextColumn::make('trip_id')
                ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT))
                ->label('Trips ID'), // Nom de la colonne
                ImageColumn::make('avatar')
                ->disk('public') // Spécifiez le disque
                ->label('Avatar')
                ->url(fn ($record) => url('storage/' . $record->avatar)) // Créez l'URL de l'image
                ->defaultImageUrl(url('/storage/avatars/default.png')),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
